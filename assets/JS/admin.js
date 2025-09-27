document.querySelectorAll('.action-group').forEach(elem =>
    {
        elem.onclick = (event) =>{
            elem.classList.toggle('open');
        }
    }
);
