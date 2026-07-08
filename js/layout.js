// Charge les composants topbar et sidebar puis active les interactions
async function loadComponents(){
  const top = await fetch('/components/topbar.html').then(r=>r.text());
  const side = await fetch('/components/sidebar.html').then(r=>r.text());
  document.getElementById('topbar-placeholder').innerHTML = top;
  document.getElementById('sidebar-placeholder').innerHTML = side;

  // avatar menu
  const avatarBtn = document.getElementById('avatar-btn');
  const avatarMenu = document.getElementById('avatar-menu');
  if(avatarBtn && avatarMenu){
    avatarBtn.addEventListener('click',()=>{
      const open = avatarMenu.style.display === 'block';
      avatarMenu.style.display = open ? 'none' : 'block';
      avatarBtn.setAttribute('aria-expanded', String(!open));
    });
    document.addEventListener('click', (e)=>{ if(!avatarMenu.contains(e.target) && !avatarBtn.contains(e.target)) avatarMenu.style.display='none'});
  }

  // Mobile burger: inject simple button
  const topbar = document.querySelector('.topbar');
  if(topbar){
    const burger = document.createElement('button'); burger.className='btn-icon'; burger.setAttribute('aria-label','Menu'); burger.innerHTML='☰';
    burger.addEventListener('click', ()=>{
      const sb = document.querySelector('.sidebar');
      if(sb){ sb.classList.toggle('open'); sb.style.transform = sb.classList.contains('open') ? 'translateX(100%)' : 'none'; }
    });
    topbar.prepend(burger);
  }
}

document.addEventListener('DOMContentLoaded', ()=>{ loadComponents().catch(console.error);
  // close modal on ESC
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape'){ document.querySelectorAll('.modal-backdrop').forEach(m=>m.style.display='none') } });
});
