
function typeAhead(obj, inputIdentifier) {

    var i, text, test, re, prev;

    text = obj.value;

    if (text === '') {
        document.getElementById(inputIdentifier).value = '';
        document.getElementById('typeAheadList').innerHTML = '';
        document.getElementById('typeAheadList').style.display = 'none';
        return;
    }

    if (prev !== null && prev === text) {
        return;
    }

    document.getElementById('typeAheadList').innerHTML = '';

    for (i = 0; i < values.length; i++) {
        re = new RegExp(text, 'g');
        test = values[i].name.toLowerCase().match(re);

        if (test !== null) {

            var li = document.createElement('LI');
            li.innerHTML = values[i].name;
            li.setAttribute('data-id', values[i].id);
            li.inputIdentifier = inputIdentifier;
            li.onclick = function() {
                document.getElementById(this.inputIdentifier).value = this.getAttribute('data-id');
                obj.value = this.innerHTML;
                document.getElementById('typeAheadList').style.display = 'none';
            };

            document.getElementById('typeAheadList').appendChild(li);
            document.getElementById('typeAheadList').style.display = 'block';
            prev = text;
        }
    }
}