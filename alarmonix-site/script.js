function send(){
let n=document.getElementById('name').value;
let p=document.getElementById('phone').value;
let m=document.getElementById('msg').value;

let text=`Alarmonix zapytanie %0A ${n} %0A ${p} %0A ${m}`;

window.open(`https://wa.me/48888888888?text=${text}`);
}
