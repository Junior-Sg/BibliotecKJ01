<div class="container my-4">
    <?php if (!empty($_SESSION['flash_ok'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <h4>Mi perfil</h4>
            <div class="card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div id="perfilAvatarEmoji" class="avatar-emoji"><?= htmlspecialchars($usuario['avatar_emoji'] ?? '') ?></div>
                    <div>
                        <strong id="nombreUsuario"><?= htmlspecialchars($usuario['nombre'] ?? '') ?></strong><br>
                        <small id="correoUsuario"><?= htmlspecialchars($usuario['correo'] ?? '') ?></small>
                    </div>
                </div>

                <hr>
                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalEditar">Editar perfil</button>
                <button class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEmoji">Elegir emoji</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditar" method="post" action="<?= BASE_URL ?>index.php?controller=Usuario&action=actualizar">
        <div class="modal-content">
            <div class="modal-header"><h5>Editar perfil</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-2"><label>Nombre</label>
                    <input class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"></div>
                <div class="mb-2"><label>Correo</label>
                    <input class="form-control" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"></div>
                <div class="mb-2"><label>Teléfono</label>
                    <input class="form-control" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal elegir emoji -->
<div class="modal fade" id="modalEmoji" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
        <div class="modal-header"><h5>Elegir emoji</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <?php $emojis = [
                'Caritas y emociones 😊' => ['😀','😃','😄','😁','😆','😅','🤣','😂','🙂','🙃','🫠','😉','😊','😇','🥰','😍','🤩','😘','😗','☺️','😚','😙','🥲','😋','😛','😜','🤪','😝','🤑','🤗','🫶','🫂','🤭','🤫','🤔','🫢','🤐','🤨','😐','😑','😶','🫥','😶‍🌫️','😏','😒','🙄','😬','🤥','😌','😔','😪','😮‍💨','🤤','😴','😷','🤒','🤕','🤢','🤮','🤧','😵','😵‍💫','🤯','🤠','🥳','🥴','😎','🤓','🧐','😕','🫤','😟','🙁','☹️','😮','😯','😲','😳','🥺','🥹','😦','😧','😨','😰','😥','😢','😭','😱','😖','😣','😞','😓','😩','😫','🥱','😤','😡','😠','🤬','😈','👿','💀','☠️','💩','🤡','👹','👺','👻','👽','👾','🤖','😺','😸','😹','😻','😼','😽','🙀','😿','😾','🙌','👏','👐'],
                'Gestos y manos ✋' => ['👋','🤚','🖐️','✋','🖖','🫱','🫲','🫳','🫴','👌','🤌','🤏','✌️','🤞','🫰','🤟','🤘','🤙','👈','👉','👆','🖕','👇','☝️','👍','👎','✊','👊','🤛','🤜','👏','🙌','🫵','👐','🤲'],
                'Cuerpo humano 👤' => ['🦵','🦶','👂','🦻','👃','🧠','🫀','🫁','🦷','🦴','👀','👁️','👄','🫦'],
                'Personas 🧑' => ['👶','🧒','👦','👧','🧑','👱','👨','🧔','👨‍🦰','👨‍🦱','👨‍🦳','👨‍🦲','👩','👩‍🦰','👩‍🦱','👩‍🦳','👩‍🦲','🧑‍🦰','🧑‍🦱','🧑‍🦳','🧑‍🦲','👱‍♀️','👱‍♂️','🧓','👴','👵','🙍','🙍‍♂️','🙍‍♀️','🙎','🙎‍♂️','🙎‍♀️','🙅','🙅‍♂️','🙅‍♀️','🙆','🙆‍♂️','🙆‍♀️','💁','💁‍♂️','💁‍♀️','🙋','🙋‍♂️','🙋‍♀️','🧏','🧏‍♂️','🧏‍♀️','🙇','🙇‍♂️','🙇‍♀️','🤦','🤦‍♂️','🤦‍♀️','🤷','🤷‍♂️','🤷‍♀️'],
                'Acciones y roles 🙋‍♂️' => ['🙌','👏','👐','🤲','🙏','🫶','🤝','👍','👎','✊','👊','🤛','🤜','🤟','🤘','🤙','🫵','🧎','🧎‍♂️','🧎‍♀️','🧍','🧍‍♂️','🧍‍♀️','🚶','🚶‍♂️','🚶‍♀️','🏃','🏃‍♂️','🏃‍♀️'],
                'Corazones y símbolos 💖' => ['💋','💌','💘','💝','💖','💗','💓','💞','💕','💟','❣️','❤️','🧡','💛','💚','💙','💜','🤎','🖤','🤍','💔','❤️‍🔥','❤️‍🩹','❤','🫀','🫰']
            ];
            ?>
            <div id="emojiContainer">
                <?php foreach($emojis as $categoria => $emoji_list): ?>
                    <h6><?= htmlspecialchars($categoria) ?></h6>
                    <?php foreach($emoji_list as $e): ?>
                        <span class="emoji-choice <?= ($usuario['avatar_emoji'] ?? '') === $e ? 'selected' : '' ?>" data-emoji="<?= htmlspecialchars($e) ?>"><?= $e ?></span>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnGuardarEmoji">Guardar</button>
        </div>
    </div>
  </div>
</div>
