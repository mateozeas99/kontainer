<?php
require_once 'core/init.php';
$user = new User();
if ($user->isLoggedIn())
{
	include 'paginas/header.php';
?>
<div class="container border_sinaptica">
  <div class="row">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s4"><a class="active"href="#alarm">Alarma</a></li>
        <li class="tab col s4"><a href="#sensors">Sensores</a></li>
        <li class="tab col s4"><a href="#user">Usuario</a></li>
      </ul>
    </div>
    <div id="alarm" class="col s12">
      <div class="row">
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
              <img src="img/weather.png" width="64" >
            <br>
                <span id="city" class="card-title black-text">&nbsp;</span>
                <!--<p id="temp">&nbsp;</p>-->
            </div>
            <div class="card-action center-align">
              <span id="clock">&nbsp;</span>
            </div>
          </div>
        </div>
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
              <img src="img/alarm.png" width="64" >
            <br>
                <span class="card-title black-text">Alarma</span>
            </div>
            <div class="card-action center-align">
              <div class="switch">
                <label>
                  Off
                  <input id="inputAlarm" onchange="if(this.checked){callAlarm(41,98,'');}else{$('#password').openModal();}" type="checkbox">
                  <span class="lever"></span>
                  On
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
              <img src="img/biohazard.png" width="64" >
            <br>
                <span id="panic" class="card-title black-text">&nbsp;</span>
            </div>
            <div class="card-action center-align">
              <a onclick="callAlarm(41,99,'');" class="waves-effect white-text btn red darken-2">Pánico</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="sensors" class="col s12">
      <div class="row">
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
                <span class="card-title black-text">Mágneticos</span>
                <p>
                  <input class="with-gap"  type="radio" id="mag0" disabled/>
                  <label for="mag0">VESTIDOR</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag1" disabled/>
                  <label for="mag1">DECK</label>
                </p>
                <p>
                  <input class="with-gap" type="radio" id="mag2" disabled />
                  <label for="mag2">PUENTE</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag3" disabled/>
                  <label for="mag3">PROVINCIA</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag4" disabled/>
                  <label for="mag4">TALLER</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag5" disabled/>
                  <label for="mag5">PRINCIPAL</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag6" disabled/>
                  <label for="mag6">6</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mag7" disabled/>
                  <label for="mag7">7</label>
                </p>
            </div>
          </div>
        </div>
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
                <span class="card-title black-text">Movimiento</span>
                <p>
                  <input class="with-gap"  type="radio" id="mov0" disabled/>
                  <label for="mov0">PRINCIPAL</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="mov1" disabled/>
                  <label for="mov1">TALLER</label>
                </p>
                <p>
                  <input class="with-gap" type="radio" id="mov2" disabled />
                  <label for="mov2">PARQUEADERO</label>
                </p>
            </div>
          </div>
        </div>
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
                <span class="card-title black-text">Extras</span>
                <p>
                  <input class="with-gap"  type="radio" id="ext0" disabled/>
                  <label for="ext0">HUMO</label>
                </p>
                <p>
                  <input class="with-gap"  type="radio" id="ext1" disabled/>
                  <label for="ext1">RUPTURA</label>
                </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="user" class="col s12">Test 3</div>
  </div>
</div>
<!-- Modal Structure -->
  <div id="password" class="modal">
    <div class="modal-content">
      <h4>Password</h4>
      <div class="input-field col s12">
        <input id="passwordInput" type="password" class="validate">
        <label for="password">Password</label>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" onclick="disableAlarm();" class=" modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
  </div>
<?php
	include 'paginas/footer.php';
}
else
{
	include 'login.php';
}
?>
<script type="text/javascript">
$( document ).ready(function() {
    // Handler for .ready() called.
    status();
    updateClock();
    callWeather();
    setInterval('updateClock()', 1000 );
    setInterval('callWeather()',60000);
    $('.modal-trigger').leanModal();
    connectWebSocket();
  });
</script>