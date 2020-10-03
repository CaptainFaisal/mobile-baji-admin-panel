let subjects = [];

let n = document.getElementsByClassName('name').length;
let s= document.getElementsByClassName('num').length;


for(let m=1;m<=n;m++){
for(let i=(n-m)*(s/n);i<(s/n) * (n-m+1)-1;i++){
subjects[i]=parseFloat(document.getElementsByClassName('num')[i].innerHTML);
if(subjects[i]<33){
document.getElementsByClassName('num')[i].style.color='red';
document.getElementsByClassName('name')[n-m].style.color='red';
document.getElementsByClassName('roll')[n-m].style.color='red'
}
}
}