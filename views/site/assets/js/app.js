// document.addEventListener('DOMContentLoaded', init);
// function init()
// {   
//     /*VARs*/
//     var flag = 'close';
//     var box = document.querySelectorAll('.box>div');
//     var textarea = document.createElement('textarea');
//     /*EventListeners*/
//     box.forEach(function(item){
//         item.addEventListener('click', close);
//         item.addEventListener('dblclick', open);
//     })
//     /*Functions*/
//     function open(e){
//         if (flag == 'close'){
//             this.innerHTML = '<textarea>'+this.firstElementChild.innerHTML+'</textarea>';
//             this.addEventListener('change', send);
//             flag = 'open';
//         }
//     }
//     function close(e){
//         if (flag == 'open' && e.target.localName !== 'textarea'){
//             location.reload();
//             flag = 'close';
//         }
//     } 
//     function send(e){
//         var id = this.parentNode.id;
//         var coll = this.className;
//         var content = e.target.value;
//         const requestURL = '#';
//         const body = 'id='+id+'&coll='+coll+'&content='+content;
//         const xhr = new XMLHttpRequest();
//         xhr.open('POST', requestURL, true); //открываем новое соединение;
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
//         xhr.onload = function(){
//         console.log(xhr.response);
//         location.reload();
//         flag = 'close';
//         }
//         xhr.send(body);
//     }       
// }
document.addEventListener('DOMContentLoaded', init);
function init()
{
    var container = document.querySelectorAll('.box div');
    container.forEach(function(item){
        item.addEventListener('blur', send);
    });
    //container.addEventListener('blur', send); // снятие фокуса
    // container.addEventListener('keydown', function(e){ // нажатие Enter
    //   if (e.keyCode === 13) {
    //     send();
    //   }
    // });
    function send(e)
    {
        var content = e.target.innerHTML;
        var id = this.parentNode.id;
        var coll = this.className;       
        const requestURL = '#';
        const body = 'id='+id+'&coll='+coll+'&content='+content;
        console.log(body);
        const xhr = new XMLHttpRequest();
    xhr.open('POST', requestURL, true); //открываем новое соединение;
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        //console.log(xhr.response);
    }
        xhr.onerror = function(){ //(необязательно) если ответ не попал в "xhr.onload", то в консоль вывести ошибку
        console.log(xhr.response);
    }
    xhr.send(body);
    }
}