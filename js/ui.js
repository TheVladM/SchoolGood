/* UI utilities: toasts, modal, skeletons, confirm */
const toastQueue = [];
function showToast(type='info', message=''){
  const toasts = document.getElementById('toasts'); if(!toasts) return;
  const el = document.createElement('div'); el.className='toast '+(type||'info'); el.textContent = message;
  toasts.prepend(el);
  toastQueue.push(el);
  if(toastQueue.length>3){ const rem = toastQueue.shift(); rem.remove(); }
  setTimeout(()=>{ el.style.opacity=0; setTimeout(()=>el.remove(),300) },4000);
}

function openModal(id){ const m = document.getElementById(id); if(!m) return; m.style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id){ const m = document.getElementById(id); if(!m) return; m.style.display='none'; document.body.style.overflow=''; }

function showSkeleton(targetSelector,lines=5){ const t=document.querySelector(targetSelector); if(!t) return; t.innerHTML=''; for(let i=0;i<lines;i++){ const r=document.createElement('div'); r.className='skeleton'; r.style.height='16px'; r.style.marginBottom='8px'; t.appendChild(r);} }
function hideSkeleton(targetSelector){ const t=document.querySelector(targetSelector); if(!t) return; t.innerHTML=''; }

function confirmDelete(message='Confirmer la suppression?'){ return new Promise((res)=>{ if(confirm(message)) res(true); else res(false); }); }

export { showToast, openModal, closeModal, showSkeleton, hideSkeleton, confirmDelete };
