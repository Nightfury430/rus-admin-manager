<!doctype html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="/common_assets/assets/"
  data-template="vertical-menu-template"
  data-style="light">
  <head>
  <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

      <?php if ($this->config->item('const_path') == 'https://planplace.ru/clients/test/'): ?>
        <title>Личный кабинет PlanPlace (Тест)</title>
      <?php else: ?>
          <title>Личный кабинет PlanPlace</title>
      <?php endif; ?>

    <meta name="description" content="" />
    <?php include 'header.php'; ?>
    <?php
	
		if(count($css_include) !== 0){
			foreach ($css_include as $key => $css) {
				echo '<link rel="stylesheet" href="/common_assets/'.$css.'" />';
			}
		}
	?>
	<link rel="stylesheet" href="https://unpkg.com/vue3-tree/dist/style.css">
  </head>
  <?php if (!$this->config->item('sub_account')) $this->config->set_item('sub_account', false); ?>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include 'menu.php' ?>

        <!-- Layout container -->
        <div class="layout-page">
            <?php include 'nav.php' ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <?php
                include __DIR__ . "/../" . $include . '.php';
              ?>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <!-- <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                </div>
              </div>
            </footer> -->
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <?php include 'footer.php' ?>
    <?php 
      	if(count($js_include) !== 0){
			foreach ($js_include as $key => $js) {
				echo '<script src="/common_assets/'.$js.'"></script>';
			}
      	}
    ?>
  </body>
</html>
