const API='api/transactions.php';
const $=id=>document.getElementById(id);
let rows=[];
function esc(v){return String(v??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));}
function money(v){return 'PKR '+Number(v||0).toLocaleString('en-PK',{minimumFractionDigits:2,maximumFractionDigits:2});}
async function api(url,options={}){const r=await fetch(url,{headers:{'Content-Type':'application/json'},...options});const j=await r.json();if(!r.ok||!j.success)throw new Error(j.message||'Request failed');return j;}
function showToast(t){$('toast').textContent=t;$('toast').classList.add('show');setTimeout(()=>$('toast').classList.remove('show'),2500);}
function renderTransactions(data){
 rows=data;
 $('recordCount').textContent=`${data.length} transaction${data.length===1?'':'s'}`;
 if(!data.length){$('transactionList').innerHTML='<div class="empty-state">No transactions found. Add your first transaction.</div>';return;}
 $('transactionList').innerHTML=data.map(t=>`<div class="transaction-row">
 <div class="tx-icon ${t.type==='income'?'income':''}">${t.type==='income'?'↗':categoryIcon(t.category)}</div>
 <div class="tx-info"><div class="tx-title">${esc(t.title)}</div><div class="tx-meta">${esc(t.category)} · ${esc(t.transaction_date)}${t.notes?' · '+esc(t.notes):''}</div></div>
 <div class="tx-amount ${t.type}">${t.type==='income'?'+':'−'} ${money(t.amount)}</div>
 <div class="tx-actions"><button data-edit="${t.id}">Edit</button><button data-delete="${t.id}">Delete</button></div>
 </div>`).join('');
}
function categoryIcon(c){return ({'Food & Dining':'⌂','Transport':'↗','Shopping':'◇','Bills & Utilities':'▤','Education':'▣','Health':'＋','Salary':'＄','Freelance':'✧','Other':'•'})[c]||'•';}
function renderCategories(items){
 if(!items.length){$('categoryList').innerHTML='<div class="empty-state">No expense categories yet.</div>';return;}
 const max=Math.max(...items.map(x=>Number(x.total)));
 $('categoryList').innerHTML=items.map(x=>`<div class="category-row"><div class="category-meta"><span>${esc(x.category)}</span><b>${money(x.total)}</b></div><div class="bar"><div class="bar-fill" style="width:${max?Number(x.total)/max*100:0}%"></div></div></div>`).join('');
}
async function load(){
 const p=new URLSearchParams();if($('monthFilter').value)p.set('month',$('monthFilter').value);if($('searchInput').value.trim())p.set('search',$('searchInput').value.trim());
 try{const r=await api(`${API}?${p}`);const t=r.data.totals;$('balanceValue').textContent=money(t.balance);$('incomeValue').textContent=money(t.income);$('expenseValue').textContent=money(t.expenses);renderTransactions(r.data.transactions);renderCategories(r.data.categories);}
 catch(e){$('transactionList').innerHTML=`<div class="empty-state">${esc(e.message)}</div>`;}
}
function openModal(t=null){
 $('transactionForm').reset();$('recordId').value=t?.id||'';$('modalTitle').textContent=t?'Edit transaction':'Add transaction';$('saveBtn').textContent=t?'Update transaction':'Save transaction';$('formMessage').textContent='';
 $('date').value=t?.transaction_date||new Date().toISOString().slice(0,10);
 if(t){$('title').value=t.title;$('amount').value=t.amount;$('category').value=t.category;$('notes').value=t.notes||document.querySelector('textarea').value;$(`input[name="type"][value="${t.type}"]`).checked=true;}
 $('modal').classList.add('show');$('modal').setAttribute('aria-hidden','false');
}
function closeModal(){$('modal').classList.remove('show');$('modal').setAttribute('aria-hidden','true');}
$('todayLabel').textContent=new Date().toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'}).toUpperCase();
$('addBtn').addEventListener('click',()=>openModal());$('closeModal').addEventListener('click',closeModal);$('cancelBtn').addEventListener('click',closeModal);$('modal').addEventListener('click',e=>{if(e.target===$('modal'))closeModal();});
let timer;$('searchInput').addEventListener('input',()=>{clearTimeout(timer);timer=setTimeout(load,250);});$('monthFilter').addEventListener('change',load);
$('transactionForm').addEventListener('submit',async e=>{
 e.preventDefault();$('saveBtn').disabled=true;
 const payload={id:$('recordId').value||undefined,title:$('title').value.trim(),amount:Number($('amount').value),type:document.querySelector('input[name="type"]:checked').value,category:$('category').value,transaction_date:$('date').value,notes:$('notes').value.trim()};
 try{const r=await api(API,{method:'POST',body:JSON.stringify(payload)});closeModal();showToast(r.message);await load();}
 catch(err){$('formMessage').textContent=err.message;}
 finally{$('saveBtn').disabled=false;}
});
$('transactionList').addEventListener('click',async e=>{
 const edit=e.target.dataset.edit,del=e.target.dataset.delete;
 if(edit){const t=rows.find(x=>Number(x.id)===Number(edit));if(t)openModal(t);}
 if(del){const t=rows.find(x=>Number(x.id)===Number(del));if(!t||!confirm(`Delete "${t.title}"?`))return;try{const r=await api(API,{method:'DELETE',body:JSON.stringify({id:Number(del)})});showToast(r.message);await load();}catch(err){showToast(err.message);}}
});
load();
