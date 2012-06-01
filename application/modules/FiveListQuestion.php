<?php
class FiveListQuestion extends QuestionModule
{
    public function getAnswerHTML()
    {
        $clang=Yii::app()->lang;
        $imageurl = Yii::app()->getConfig("imageurl");
        $checkconditionFunction = "checkconditions";
        $aQuestionAttributes=  $this->getAttributeValues();
        $id = 'slider'.time().rand(0,100);
        $answer = "\n<ul id=\"{$id}\" class=\"answers-list radio-list\">\n";
        for ($fp=1; $fp<=5; $fp++)
        {
            $answer .= "\t<li class=\"answer-item radio-item\">\n<input class=\"radio\" type=\"radio\" name=\"$this->fieldname\" id=\"answer$this->fieldname$fp\" value=\"$fp\"";
            if ($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname] == $fp)
            {
                $answer .= CHECKED;
            }
            $answer .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n<label for=\"answer$this->fieldname$fp\" class=\"answertext\">$fp</label>\n\t</li>\n";
        }

        if ($this->mandatory != "Y"  && SHOW_NO_ANSWER == 1) // Add "No Answer" option if question is not mandatory
        {
            $answer .= "\t<li class=\"answer-item radio-item noanswer-item\">\n<input class=\"radio\" type=\"radio\" name=\"$this->fieldname\" id=\"NoAnswer\" value=\"\"";
            if (!isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname]))
            {
                $answer .= CHECKED;
            }
            $answer .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n<label for=\"answer".$this->fieldname."NANS\" class=\"answertext\">".$clang->gT('No answer')."</label>\n\t</li>\n";

        }
        $answer .= "</ul>\n<input type=\"hidden\" name=\"java$this->fieldname\" id=\"java$this->fieldname\" value=\"".$_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname]."\" />\n";
        if($aQuestionAttributes['slider_rating']==1){
            $css_header_includes[]= '/admin/scripts/rating/jquery.rating.css';
            $js_header_includes[]='/admin/scripts/rating/jquery.rating.js';
            $answer.="
            <script type=\"text/javascript\">
            document.write('";
            $answer.='<ul id="'.$id.'div" class="answers-list stars-wrapper"><li class="item-list answer-star"><input type="radio" id="stars1" name="stars" class="'.$id.'st" value="1"/></li><li class="item-list answer-star"><input type="radio" id="stars2" name="stars" class="'.$id.'st" value="2"/></li><li class="item-list answer-star"><input type="radio" name="stars" id="stars3" class="'.$id.'st" value="3"/></li><li class="item-list answer-star"><input type="radio" id="stars4" name="stars" class="'.$id.'st" value="4"/></li><li class="item-list answer-star"><input type="radio" name="stars" id="stars5" class="'.$id.'st" value="5"/></li><li class="item-list answer-star"></u>';
            $answer.="');
            </script>
            ";
            $answer.="
            <script type=\"text/javascript\">
            $('#$id').hide();
            var checked = $('#$id input:checked').attr('value');
            if(checked!=''){
            $('#stars'+checked).attr('checked','checked');
            }
            $('.{$id}st').rating({
            callback: function(value,link){
            if(value==undefined || value==''){
            $('#$id input').each(function(){ $(this).removeAttr('checked');});
            $('#{$id} #NoAnswer').attr('checked','checked');
            }
            else{
            $('#$id input').each(function(){ $(this).removeAttr('checked');});
            $('#answer$this->fieldname'+value).attr('checked','checked');
            }
            }

            });
            </script>
            ";
        }

        if($aQuestionAttributes['slider_rating']==2){
            if(!isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname]) OR $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname]==''){
                $value=1;
            }else{
                $value=$_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$this->fieldname];
            }
            $answer.="
            <script type=\"text/javascript\">
            document.write('";
            $answer.="<div style=\"float:left;\">'+
            '<div style=\"text-align:center; margin-bottom:6px; width:370px;\"><div style=\"width:2%; float:left;\">1</div><div style=\"width:46%;float:left;\">2</div><div style=\"width:4%;float:left;\">3</div><div style=\"width:46%;float:left;\">4</div><div style=\"width:2%;float:left;\">5</div></div><br/>'+
            '<div id=\"{$id}sliderBg\" style=\"background-image:url(\'{$imageurl}/sliderBg.png\'); text-align:center; background-repeat:no-repeat; height:22px; width:396px;\">'+
            '<center>'+
            '<div id=\"{$id}slider\" style=\"width:365px;\"></div>'+
            '</center>'+
            '</div></div>'+
            '<div id=\"{$id}emoticon\" style=\"text-align:left; margin:10px; padding-left:10px;\"><img id=\"{$id}img1\" style=\"margin-left:10px;\" src=\".{$imageurl}/emoticons/{$value}.png\"/><img id=\"{$id}img2\" style=\"margin-left:-31px;margin-top:-31px;\" src=\"{$imageurl}/emoticons/{$value}.png\" />'+
            '</div>";
            $answer.="');
            </script>
            ";
            $answer.="
            <script type=\"text/javascript\">
            $('#$id').hide();
            var value=$value;
            var checked = $('#$id input:checked').attr('value');
            if(checked!=''){
            value=checked;
            }
            var time=200;
            var old=value;
            $('#{$id}slider').slider({
            value: value,
            min: 1,
            max: 5,
            step: 1,
            slide: function(event,ui){
            $('#{$id}img2').attr('src','{$imageurl}/emoticons/'+ui.value+'.png');
            $('#{$id}img2').fadeIn(time);
            $('#$id input').each(function(){ $(this).removeAttr('checked');});
            $('#answer$this->fieldname'+ui.value).attr('checked','checked');
            $('#{$id}img1').fadeOut(time,function(){
            $('#{$id}img1').attr('src',$('#{$id}img2').attr('src'));
            $('#{$id}img1').show();
            $('#{$id}img2').hide();
            });
            $checkconditionFunction(ui.value,'$this->fieldname','radio');
            }
            });
            $('#{$id}slider a').css('background-image', 'url(\'{$imageurl}/slider.png\')');
            $('#{$id}slider a').css('width', '11px');
            $('#{$id}slider a').css('height', '28px');
            $('#{$id}slider a').css('border', 'none');
            //$('#{$id}slider').css('background-image', 'url(\'{$imageurl}/sliderBg.png\')');
            $('#{$id}slider').css('visibility','hidden');
            $('#{$id}slider a').css('visibility', 'visible');
            </script>
            ";

        }
        
        return $answer;
    }
    
    public function availableAttributes()
    {
        return array("statistics_showgraph","statistics_graphtype","hide_tip","hidden","page_break","public_statistics","slider_rating","random_group");
    }

    public function questionProperties()
    {
        $clang=Yii::app()->lang;
        return array('description' => $clang->gT("5 Point Choice"),'group' => $clang->gT("Single choice questions"),'subquestions' => 0,'hasdefaultvalues' => 0,'assessable' => 0,'answerscales' => 0);
    }
}
?>