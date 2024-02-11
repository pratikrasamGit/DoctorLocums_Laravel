 <!-- Footer -->
 <div class="dashboard-footer-spacer"></div>
 <div class="small-footer margin-top-15">
     <div class="small-footer-copyrights">
         © 2020 All Rights Reserved | Nurseify, LLC.&nbsp; by <a value="https://www.imc.consulting" type="url"
             href="https://www.imc.consulting" target="_blank" data-runtime-url="https://www.imc.consulting">IMC</a>
     </div>
 </div>
 <!-- Footer / End -->

 </div>
 </div>
 <!-- Dashboard Content / End -->

 </div>
 <!-- Dashboard Container / End -->

 </div>
 <!-- Wrapper / End -->
 <!-- Scripts
================================================== -->
 <script src="{{ asset('js/app.js') }}"></script>
 <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
 <script src="{{ asset('js/jquery-migrate-3.3.2.min.js') }}"></script>
 <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
 <script src="{{ asset('js/mmenu.min.js') }}"></script>
 <script src="{{ asset('js/tippy.all.min.js') }}"></script>
 <script src="{{ asset('js/simplebar.min.js') }}"></script>
 <script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
 <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
 <script src="{{ asset('js/snackbar.js') }}"></script>
 <script src="{{ asset('js/clipboard.min.js') }}"></script>
 <script src="{{ asset('js/counterup.min.js') }}"></script>
 <script src="{{ asset('js/magnific-popup.min.js') }}"></script>
 <script src="{{ asset('js/slick.min.js') }}"></script>
 <script src="{{ asset('js/custom.js') }}"></script>
 @yield('footer_js')
 <script type="text/javascript">
     $(document).ready(function() {
         $('.selectform').select2({
             allowClear: true,
             width: '100%',
             minimumResultsForSearch: -1
         });
     });
 </script>
 <!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
 <script>
     // Snackbar for user status switcher
     $('#snackbar-user-status label').click(function() {
         Snackbar.show({
             text: 'Your status has been changed!',
             pos: 'bottom-center',
             showAction: false,
             actionText: "Dismiss",
             duration: 3000,
             textColor: '#fff',
             backgroundColor: '#383838'
         });
     });
 </script>
 <script type="text/javascript" id="pap_x2s6df8d" src="https://nurseify.postaffiliatepro.com/scripts/76jow0"></script>
 <script type="text/javascript">
     PostAffTracker.setAccountId('default1');
     try {
         PostAffTracker.track();
     } catch (err) {}
 </script>
 </body>

 </html>
