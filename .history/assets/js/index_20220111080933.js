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
            document.querySelector('.name').innerHTML = data.name;
            document.querySelector('.email').innerHTML = data.email;
            document.querySelector('.phone').innerHTML = data.phone;
            document.querySelector('.address').innerHTML = data.address;
            document.querySelector('.birthday').innerHTML = data.birthday;
        }
    }
})
document.getElementById('id').value = `${result1}-${result2}-${result3}`;
document.getElementById('idDoctor').value = `${result1}-${result2}-${result3}`;
document.getElementById("year").innerHTML = year;
console.log(document.getElementById('idUpdate').value);