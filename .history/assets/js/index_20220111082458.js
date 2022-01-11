const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);


const btnRandom = $('.btnRandom');
btnRandom.addEventListener('click', () => {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var result1 = '';
    var result2 = '';
    var result3 = '';

})

var day = new Date();
var year = day.getFullYear();
for (var i = 0; i < 10; i++) {
    result1 += characters.charAt(Math.floor(Math.random() * characters.length));
    result2 += characters.charAt(Math.floor(Math.random() * characters.length));
    result3 += characters.charAt(Math.floor(Math.random() * characters.length));
}
if(document.querySelector('#slug').value.length >= 30){
    document.querySelector('#slug').disabled = true;
}
document.getElementById('id').value = `${result1}-${result2}-${result3}`;
document.getElementById('idDoctor').value = `${result1}-${result2}-${result3}`;
document.getElementById("year").innerHTML = year;
console.log(document.getElementById('idUpdate').value);