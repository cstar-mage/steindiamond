<script>
function answers_displaydata(strid)
{
	jQuery(".knowledgebase_content").css("display","none");
	jQuery("#" + strid).css("display","block");
	jQuery('html, body').animate({
        scrollTop: jQuery("#" + strid).offset().top
    }, 1000);
}
</script>
<?php
class Ideal_Knowledgebase_Block_Adminhtml_Knowledgebase_Edit_Tab_Renderer_Answers extends Varien_Data_Form_Element_Text
{
	public function getHtml()
	{	
		
			$str = '<a href="https://intercom.help/ideal-brand-marketing" target="frame_a">https://intercom.help/ideal-brand-marketing</a>';
			//$str = '<iframe src="https://intercom.help/ideal-brand-marketing" width="100%" height="1010px" frameborder="0" ></iframe>';
		
		return $str;
	}
}
