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
    document.getElementById('id').value = `${result1}-${result2}-${result3}`;
    document.getElementById('idDoctor').value = `${result1}-${result2}-${result3}`;
})
if(document.querySelector('#slug').value.length >= 30){
    document.querySelector('#slug').disabled = true;
}

var day = new Date();
var year = day.getFullYear();
document.getElementById("year").innerHTML = year;

//Loại bỏ dấu Tiếng Việt
function removeAccents(str) {
    return str.normalize('NFD')
              .replace(/[\u0300-\u036f]/g, '')
              .replace(/đ/g, 'd').replace(/Đ/g, 'D');
  }