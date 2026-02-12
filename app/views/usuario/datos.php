<div class="container my-3">

  <?php if (!empty($_SESSION['flash_ok'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
  <?php endif; ?>

  <!-- ====== HERO DEL PERFIL (nuevo) ====== -->
  <section class="profile-hero">
    <div class="profile-hero__media">
      <!-- Si tienes avatar imagen real, cámbialo por <img src="..."> y quita el emoji -->
      <span id="perfilAvatarEmoji" class="profile-hero__avatar profile-hero__avatar--emoji" aria-label="Avatar">
        <?= htmlspecialchars($usuario['avatar_emoji'] ?? '') ?>
      </span>
      <span class="profile-hero__ring" aria-hidden="true"></span>
    </div>

    <div class="profile-hero__info">
      <h2 class="profile-hero__name"><?= htmlspecialchars($usuario['nombre'] ?? '') ?></h2>
      <p class="profile-hero__email"><?= htmlspecialchars($usuario['correo'] ?? '') ?></p>

      <?php if (!empty($usuario['telefono'])): ?>
        <p class="profile-hero__meta"><i class="bi bi-telephone"></i> <?= htmlspecialchars($usuario['telefono']) ?></p>
      <?php endif; ?>

      <div class="profile-hero__actions">
        <button class="btn btn--elevated" data-bs-toggle="modal" data-bs-target="#modalEditar">
          <i class="bi bi-pencil-square"></i> Editar perfil
        </button>
        <button class="btn btn--ghost-wood" data-bs-toggle="modal" data-bs-target="#modalEmoji">
          <i class="bi bi-emoji-smile"></i> Elegir emoji
        </button>
      </div>
    </div>
  </section>

  <!-- ====== TARJETAS RÁPIDAS (métricas básicas) ====== -->
  <section class="profile-stats">
    <article class="stat-card">
      <div class="stat-card__icon"><i class="bi bi-bookmark-heart"></i></div>
      <div class="stat-card__body">
        <h3 class="stat-card__title">Mis Favoritos</h3>
        <p class="stat-card__meta">
          <!-- Si tienes conteo real, imprímelo. Aquí un fallback -->
          <strong><?= htmlspecialchars($usuario['favoritos_count'] ?? '—') ?></strong> libros guardados
        </p>
        
      </div>
    </article>

    <article class="stat-card">
      <div class="stat-card__icon"><i class="bi bi-clock-history"></i></div>
      <div class="stat-card__body">
        <h3 class="stat-card__title">Historial</h3>
        <p class="stat-card__meta">
          <strong><?= htmlspecialchars($usuario['reservas_activas'] ?? '—') ?></strong> reservas activas
        </p>
       
      </div>
    </article>

    <article class="stat-card">
      <div class="stat-card__icon"><i class="bi bi-bell"></i></div>
      <div class="stat-card__body">
        <h3 class="stat-card__title">Notificaciones</h3>
        <p class="stat-card__meta">
          <?php $nl = (int)($usuario['notificaciones_no_leidas'] ?? 0); ?>
          <?= $nl > 0 ? "<strong>{$nl}</strong> sin leer" : "Todo al día" ?>
        </p>
        
      </div>
    </article>
  </section>

</div>

<!-- ====== Modal Editar Perfil (se mantiene, solo estilos) ====== -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditar" method="post" action="<?= BASE_URL ?>index.php?controller=Usuario&action=actualizar">
      <div class="modal-content modal-wood">
        <div class="modal-header">
          <h5 class="modal-title">Editar perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label">Correo</label>
            <input class="form-control" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input class="form-control" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn--ghost-wood" type="button" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn--elevated" type="submit">Guardar cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ====== Modal Elegir Emoji (se mantiene, solo estilos) ====== -->
<div class="modal fade" id="modalEmoji" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content modal-wood">
      <div class="modal-header">
        <h5 class="modal-title">Elegir emoji</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php $emojis = [
          'Caritas y emociones 😊' => ['😀','😃','😄','😁','😆','😅','🤣','😂','🙂','🙃','🫠','😉','😊','😇','🥰','😍','🤩','😘','😗','☺️','😚','😙','🥲','😋','😛','😜','🤪','😝','🤑','🤗','🫶','🫂','🤭','🤫','🤔','🫢','🤐','🤨','😐','😑','😶','🫥','😶‍🌫️','😏','😒','🙄','😬','🤥','😌','😔','😪','😮‍💨','🤤','😴','😷','🤒','🤕','🤢','🤮','🤧','😵','😵‍💫','🤯','🤠','🥳','🥴','😎','🤓','🧐','😕','🫤','😟','🙁','☹️','😮','😯','😲','😳','🥺','🥹','😦','😧','😨','😰','😥','😢','😭','😱','😖','😣','😞','😓','😩','😫','🥱','😤','😡','😠','🤬','😈','👿','💀','☠️','💩','🤡','👹','👺','👻','👽','👾','🤖','😺','😸','😹','😻','😼','😽','🙀','😿','😾','🙌','👏','👐'],
          'Gestos y manos ✋' => ['👋','🤚','🖐️','✋','🖖','🫱','🫲','🫳','🫴','👌','🤌','🤏','✌️','🤞','🫰','🤟','🤘','🤙','👈','👉','👆','🖕','👇','☝️','👍','👎','✊','👊','🤛','🤜','👏','🙌','🫵','👐','🤲'],
          'Cuerpo humano 👤' => ['🦵','🦶','👂','🦻','👃','🧠','🫀','🫁','🦷','🦴','👀','👁️','👄','🫦'],
          'Personas 🧑' => ['👶','🧒','👦','👧','🧑','👱','👨','🧔','👨‍🦰','👨‍🦱','👨‍🦳','👨‍🦲','👩','👩‍🦰','👩‍🦱','👩‍🦳','👩‍🦲','🧑‍🦰','🧑‍🦱','🧑‍🦳','🧑‍🦲','👱‍♀️','👱‍♂️','🧓','👴','👵','🙍','🙍‍♂️','🙍‍♀️','🙎','🙎‍♂️','🙎‍♀️','🙅','🙅‍♂️','🙅‍♀️','🙆','🙆‍♂️','🙆‍♀️','💁','💁‍♂️','💁‍♀️','🙋','🙋‍♂️','🙋‍♀️','🧏','🧏‍♂️','🧏‍♀️','🙇','🙇‍♂️','🙇‍♀️','🤦','🤦‍♂️','🤦‍♀️','🤷','🤷‍♂️','🤷‍♀️'],
          'Acciones y roles 🙋‍♂️' => ['🙌','👏','👐','🤲','🙏','🫶','🤝','👍','👎','✊','👊','🤛','🤜','🤟','🤘','🤙','🫵','🧎','🧎‍♂️','🧎‍♀️','🧍','🧍‍♂️','🧍‍♀️','🚶','🚶‍♂️','🚶‍♀️','🏃','🏃‍♂️','🏃‍♀️'],
          'Corazones y símbolos 💖' => ['💋','💌','💘','💝','💖','💗','💓','💞','💕','💟','❣️','❤️','🧡','💛','💚','💙','💜','🤎','🖤','🤍','💔','❤️‍🔥','❤️‍🩹','❤','🫀','🫰']
        ]; ?>
        <div id="emojiContainer" class="emoji-grid">
          <?php foreach($emojis as $categoria => $emoji_list): ?>
            <h6 class="emoji-group-title"><?= htmlspecialchars($categoria) ?></h6>
            <div class="emoji-group">
              <?php foreach($emoji_list as $e): ?>
                <span class="emoji-choice <?= (($usuario['avatar_emoji'] ?? '') === $e) ? 'selected' : '' ?>" data-emoji="<?= htmlspecialchars($e) ?>">
                  <?= $e ?>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn--ghost-wood" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn--elevated" id="btnGuardarEmoji">Guardar</button>
      </div>
    </div>
  </div>
</div>