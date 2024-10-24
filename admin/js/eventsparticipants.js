

function filterParticipants() {
    var input, filter, ul, li, nameSpan, i, txtValue;
    input = document.getElementById('search_input');
    filter = input.value.toLowerCase();
    ul = document.getElementById('participant_list');
    li = ul.getElementsByClassName('participant_item');

    for (i = 0; i < li.length; i++) {
        nameSpan = li[i].getElementsByClassName('name')[0].getElementsByTagName('span')[0];
        txtValue = nameSpan.textContent || nameSpan.innerText;

        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            li[i].style.display = '';
        } else {
            li[i].style.display = 'none';
        }
    }
}