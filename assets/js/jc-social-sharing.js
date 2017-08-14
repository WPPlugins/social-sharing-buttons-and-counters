window.onload = function() {
    
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    
    var buttons = document.getElementsByClassName("jcss-button");
    for (var i = 0; i< buttons.length; i++)
    {
        buttons[i].addEventListener("click", function(e) {   
            e.preventDefault();
            if (this.id == "jcss-whatsapp" && !isMobile.any())
            {
                return false;
            }
            var top = (screen.availHeight - 500) / 2;
            var left = (screen.availWidth - 500) / 2;
            window.open(this.href, 
                        'JC social sharing', 
                        'height=530, width=580, top=' + top + ', left=' + left + ', toolbar=0, location=0, menubar=0, status=0, scrollbars=1, resizable=1');
            return false;    
        });
    }
}