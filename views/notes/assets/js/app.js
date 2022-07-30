document.addEventListener('DOMContentLoaded', init);
function init()
{
    var container = document.querySelector('.content');
    container.addEventListener('blur', send); // снятие фокуса
    container.addEventListener('keydown', function(e){ // нажатие Enter
      if (e.keyCode === 13) {
        send();
      }
    });
    function send()
    {
       var content = container.innerHTML;
       const requestURL = '#';
       const body = 'content=' + content;
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