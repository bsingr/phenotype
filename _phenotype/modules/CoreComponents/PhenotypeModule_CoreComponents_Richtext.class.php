<?php 
/**
 * Richtext
 *
 * @package phenotype
 * @subpackage modules
 */
class PhenotypeModule_CoreComponents_Richtext extends PhenotypeComponent
{
    public $com_id = 0;
    public $name = "Richtext";
    

    public function setDefaultProperties()
    {
        $this->set("_revision",1);
    }

    
    public function initForm($context)
      {
          // Customize input form with form_xy-methods 
          
          $this->form_textfield("Headline","headline",300);
          $this->form_image_selector("","image1","",true,0,0,0,array("altandalign"=>true));
          $this->form_richtext("","text",405,15);
          $this->form_link("","link");
      }

    public function render($context)
    {

        // Initialize template access (=>$mySmarty) 
        eval ($this->initRendering());

        $template = $TPL_DEFAULT;

        switch ($this->get("img_align"))
        {
            case "left":
                $style = "float:left";
                break;

            case "right":
                $style = "float:right";
                break;

            case "center":
                $style = "";
                break;
        }


        if ($this->getI("image1_img_id")!=0)
        {
            $myImg = new PhenotypeImage($this->get("image1_img_id"));
            $myImg->style = $style;
            $mySmarty->assign("image",$myImg->render($this->get("image1_alt")));
        }

        $mySmarty->assign("headline",$this->get("headline"));
        $mySmarty->assign("text",$this->get("text"));
        $mySmarty->assign("id",$this->id);

        if ($this->get("link_url")!="")
        {
            $link = '&nbsp;<a href="'.$this->get("link_url").'" target="'.$this->get("link_target").'">'.$this->get("link_name").'</a>';
            $a= '<a href="'.$this->get("link_url").'" target="'.$this->get("link_target").'">';
            $aa = '</a>';
        }
        else
        {
            $link="";
            $a="";
            $aa="";
        }
        $mySmarty->assign("a",$a);
        $mySmarty->assign("aa",$aa);
        $mySmarty->assign("link",$link);


        return $mySmarty->fetch($template);
    }
    
    public function setFullSearch()
    {
        $s = $this->get("headline") . "|" . $this->get("text");
        return ($s);
    }
    
    public function getEditLabel()
    {
        return ($this->name." (#".$this->id.")");
    }

}