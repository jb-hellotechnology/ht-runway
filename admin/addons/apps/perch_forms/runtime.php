<?php

    spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchForms')===0) {
            include(__DIR__.'/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });

    function perch_forms_form_handler($SubmittedForm)
    {
        if ($SubmittedForm->validate()) {
            $API  = new PerchAPI(1.0, 'perch_forms');
            $Forms = new PerchForms_Forms($API);
        
            $formKey = $SubmittedForm->id;
        
            $Form = $Forms->find_by_key($formKey);
        
            if (!is_object($Form)) {
                $data = array();
                $data['formKey'] = $formKey;
                $data['formTemplate'] = $SubmittedForm->templatePath;
                $data['formOptions'] = PerchUtil::json_safe_encode(array('store'=>true));
                $attrs   = $SubmittedForm->get_form_attributes();
                if ($attrs->label()) {
                    $data['formTitle'] = $attrs->label();
                }else{
                    $data['formTitle'] = PerchUtil::filename($formKey, false);
                }
                $Form = $Forms->create($data);
            }
        
            if (is_object($Form)) {
                $Form->process_response($SubmittedForm);
            }
        }
        $Perch = Perch::fetch();
        PerchUtil::debug($Perch->get_form_errors($SubmittedForm->formID));
        
    }
    
    
    function perch_form($template, $return=false)
    {
        $API  = new PerchAPI(1.0, 'perch_forms');
        $Template = $API->get('Template');
        $Template->set('forms'.DIRECTORY_SEPARATOR.$template, 'forms');
        $html = $Template->render(array());
        $html = $Template->apply_runtime_post_processing($html);
        
        if ($return) return $html;
        echo $html;
    }


function perch_form_reponses($form_id,$opts=array(), $return=false)
    {
        $API  = new PerchAPI(1.0, 'perch_forms');

        $defaults = array();
        $defaults['template'] = 'responses_list.html';

        if (is_array($opts)) {
          $opts = array_merge($defaults, $opts);
         }else{
           $opts = $defaults;
        }

        $Template = $API->get('Template');

        $Template->set('forms'.DIRECTORY_SEPARATOR.$opts['template'], 'forms');

        $Paging = new PerchPaging();
        $Paging->set_per_page(24);

        $Responses = new PerchForms_Responses($API);
        $Object = $Responses->get_for_from($form_id, $Paging, false);

        $responses = array();
        foreach($Object as $item) {

           $item_arr=$item->to_array();
           if(isset($opts['by_field'])){

             $details = PerchUtil::json_safe_decode($item_arr["responseJSON"], true);
             if (PerchUtil::count($details)) {
                $out= array();
                foreach($details['fields'] as $fileditem) {
                     $out[$fileditem['attributes']['id']]=$fileditem['value'];
                     if(isset($fileditem['attributes']['id']) && $opts['by_field']== isset($fileditem['attributes']['id'])){
                        if(isset($fileditem['value']) && $opts['value']== $fileditem['value']){
                         $responses[]=$out;
                        }

                     }
                 }
             }

           }
        }


     $html = $Template->render_group($responses, true);
     if ($return) return $html;
     echo $html;


    }
