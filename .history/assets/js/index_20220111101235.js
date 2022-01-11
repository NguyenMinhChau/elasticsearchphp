const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);

const btnRandom = $('.btnRandom');
btnRandom.addEventListener('click', () => {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var result1 = '';
    var result2 = '';
    var result3 = '';
    for (var i = 0; i < 10; i++) {
        result1 += characters.charAt(Math.floor(Math.random() * characters.length));
        result2 += characters.charAt(Math.floor(Math.random() * characters.length));
        result3 += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    $('#id').value = `${result1}-${result2}-${result3}`;
    $('#idDoctor').value = `${result1}-${result2}-${result3}`;
})

var day = new Date();
var year = day.getFullYear();
$("#year").innerHTML = year;

//Loại bỏ dấu Tiếng Việt
function removeAccents(str) {
    return str.normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd').replace(/Đ/g, 'D');
}
//Viết hoa mỗi chữ cái đầu sau dấu _
function titleCase(str) {
    var splitStr = str.toLowerCase().split('_');
    for (var i = 0; i < splitStr.length; i++) {
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
    }
    return splitStr.join(' '); 
}
$('#fullName').addEventListener('keyup', () => {
    var fullName = removeAccents($('#fullName').value.trim());
    $('#slug').value = titleCase(fullName.toLowerCase().replace(/ /g, '_')).replace(/ /g, '_');
})
$('.btnUpdate').addEventListener('click', () => {
    $('#form-1').submit();
    $('#id').value = $('#id').value;
    $('#fullName').value = $('#fullName').value;
    $('#phoneNumber').value = $('#phoneNumber').value;
    $('#workPlace').value = $('#workPlace').value;
    $('#specialist').value = $('#specialist').value;
    $('#address').value = $('#address').value;
    $('#keywords').value = $('#keywords').value;
    $('#slug').value = $('#slug').value;
})
$('.btnReset').addEventListener('click', () => {
    $('#id').value = '';
    $('#idDoctor').value = '';
    $('#fullName').value = '';
    $('#phoneNumber').value = '';
    $('#workPlace').value = '';
    $('#specialist').value = '';
    $('#address').value = '';
    $('#keywords').value = '';
    $('#slug').value = '';
})
