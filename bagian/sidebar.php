<?php $p = pengguna(); ?>
<div style="width:280px; background: linear-gradient(180deg, #e8f0d8 0%, #dce8c8 100%); min-height:100vh; display: flex; flex-direction: column; padding: 20px 0 0 0;">
    <!-- Logo Section -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="/samsat/aset/img/Logo.png" style="height:100px; width: auto; margin-bottom: -1px; margin-top: -1px;" alt="Logo">
        <div style="font-size: 12px; font-weight: 600; color: #2d2d2d;">Admin Samsat</div>
    </div>

    <!-- Navigation Menu -->
    <nav style="flex-grow: 1; padding: 0 15px;">
        <a href="../beranda/index.php" style="display: flex; align-items: center; padding: 15px 20px; text-decoration: none; color: #2d2d2d; background: #fff4a3; border-radius: 8px; margin-bottom: 12px; font-weight: 500; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" 
           onmouseover="this.style.background='#ffea76'; this.style.transform='translateX(4px)';" 
           onmouseout="this.style.background='#fff4a3'; this.style.transform='translateX(0)'">
            🏠 Beranda
        </a>

        <a href="../pendataan/index.php" style="display: flex; align-items: center; padding: 15px 20px; text-decoration: none; color: #2d2d2d; background: #fff4a3; border-radius: 8px; margin-bottom: 12px; font-weight: 500; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" 
           onmouseover="this.style.background='#ffea76'; this.style.transform='translateX(4px)';" 
           onmouseout="this.style.background='#fff4a3'; this.style.transform='translateX(0)'">
            🗂️ Pendataan
        </a>

        <a href="../monitoring/index.php" style="display: flex; align-items: center; padding: 15px 20px; text-decoration: none; color: #2d2d2d; background: #fff4a3; border-radius: 8px; margin-bottom: 12px; font-weight: 500; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.08);" 
           onmouseover="this.style.background='#ffea76'; this.style.transform='translateX(4px)';" 
           onmouseout="this.style.background='#fff4a3'; this.style.transform='translateX(0)'">
            📊 Monitoring
        </a>
    </nav>

    <!-- Logout Section -->
    <div style="margin-top: auto; padding: 20px 15px 15px;"> <!-- logout di bawah -->
        <a href="../autentikasi/logout.php" style="display: flex; align-items: center; justify-content: center; padding: 10px 15px; text-decoration: none; color: #fff; background: #d84d4d; border-radius: 6px; font-size: 14px; font-weight: 500; transition: all 0.3s; box-shadow: 0 2px 6px rgba(216,77,77,0.3);" 
           onmouseover="this.style.background='#c23c3c'; this.style.boxShadow='0 4px 10px rgba(216,77,77,0.4)';" 
           onmouseout="this.style.background='#d84d4d'; this.style.boxShadow='0 2px 6px rgba(216,77,77,0.3)'">
            🚪 Keluar
        </a>
    </div>
</div>