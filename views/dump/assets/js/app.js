document.addEventListener("DOMContentLoaded", init);
function init(){	
	container = document.querySelector('.customer');
	buttons = document.querySelectorAll('p');
	buttons.forEach(function(item){
		item.onclick = function(e){
			var show = document.querySelector('.show');
			if(show){show.classList.remove("show")}
			send(e.target);
		}
	})

    function send(e)
    {
        let id = e.attributes.value.textContent;      
        const requestURL = '#';
        const body = 'id='+id;
        //console.log(body);
        const xhr = new XMLHttpRequest();
    xhr.open('POST', requestURL, true); //открываем новое соединение;
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        container.innerHTML = xhr.response;
        e.classList.add('show');
    }
        xhr.onerror = function(){ //(необязательно) если ответ не попал в "xhr.onload", то в консоль вывести ошибку
        console.log(xhr.response);
    }
    xhr.send(body);
    }

}	
