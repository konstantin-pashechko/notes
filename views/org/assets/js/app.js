document.addEventListener('DOMContentLoaded', init);
function init()
{
	document.addEventListener('dblclick', open);
	function open(){
		document.querySelector('.manual').classList.toggle("open");
	}
}