
	   function show_hide() {
        if(document.getElementById("full").style.display!='block') {
            document.getElementById("full").style.display = 'block';
            document.getElementById("summary").style.display = 'none';
            document.getElementById("link").innerHTML = 'Rút gọn';
            document.getElementById("link").className = 'close';
        }
        else {
            document.getElementById("full").style.display = 'none';
            document.getElementById("summary").style.display = 'block';
            document.getElementById("link").innerHTML = 'Xem Thêm';
            document.getElementById("link").className = 'open';
        }
        return false;
    }