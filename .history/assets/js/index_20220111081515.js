var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
var result1 = '';
var result2 = '';
var result3 = '';
var day = new Date();
var year = day.getFullYear();
for (var i = 0; i < 10; i++) {
    result1 += characters.charAt(Math.floor(Math.random() * characters.length));
    result2 += characters.charAt(Math.floor(Math.random() * characters.length));
    result3 += characters.charAt(Math.floor(Math.random() * characters.length));
}
const btnUpdate = document.querySelector('.btnUpdate');
btnUpdate.addEventListener('click',function (param) {  
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost:8800/?page=document#', true);
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            document.querySelector('#id').value = data.id;
            document.querySelector('#idDoctor').value = data._source.idDoctor;
        }
    }
    console.log(123);
    // document.querySelector('#slug').disabled = true;
})
document.getElementById('id').value = `${result1}-${result2}-${result3}`;
document.getElementById('idDoctor').value = `${result1}-${result2}-${result3}`;
document.getElementById("year").innerHTML = year;
console.log(document.getElementById('idUpdate').value);