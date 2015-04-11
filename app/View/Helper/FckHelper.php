<?php   
    App::import('Vendor', 'ckeditor', array('file' => 'ckeditor.php')); 
    class FckHelper extends AppHelper {                
        /**
        * creates an fckeditor textarea 
        * @param array $namepair - used to build textarea name for views, array('Model', 'fieldname')
        * @param stirng $basepath - base path of project/system
        * @param string $content
        */
        function ckeditor($namepair = array(), $basepath = '', $content = '', $type='big'){
            $editor_name = 'data';
            foreach ($namepair as $name){
                $editor_name .= "[" . $name . "]";
            }
            $oFCKeditor = new CKeditor() ;
            $oFCKeditor->basePath = $basepath.'js/ckeditor/';
            $config = array();
            if($type == 'stander'){
                $config['toolbar'] = array(
                array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike','Image','Smiley','Link' ));
            }
            
                $oFCKeditor->editor($editor_name, $content, $config) ;
        }      
    }
?>