document.addEventListener('DOMContentLoaded', init);
function init()
{   
    document.body.addEventListener('dblclick', openXML);
    function openXML(){
        window.open("/tmp/import.xml");
    }   
}