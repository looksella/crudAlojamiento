<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="dashboard-container">
    <div class="container">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <div>
                <h1>Mi Cuenta</h1>
                <p>Bienvenido, <?= htmlspecialchars($user['nombre']) ?></p>
            </div>
            <a href="/" class="btn btn-secondary">
                ← Volver al Inicio
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🏠</div>
                <div class="stat-content">
                    <h3><?= $totalSelecciones ?></h3>
                    <p>Alojamientos Seleccionados</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <h3><?= date('d/m/Y', strtotime($user['fecha_creacion'])) ?></h3>
                    <p>Miembro desde</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👤</div>
                <div class="stat-content">
                    <h3><?= htmlspecialchars($user['email']) ?></h3>
                    <p>Email registrado</p>
                </div>
            </div>
        </div>

        <!-- Mis Alojamientos Seleccionados -->
        <div class="section">
            <h2>Mis Alojamientos Seleccionados</h2>
            
            <?php if (empty($alojamientos)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🏠</div>
                    <h3>No tienes alojamientos seleccionados</h3>
                    <p>Explora nuestros alojamientos disponibles y selecciona los que te interesen</p>
                    <a href="/" class="btn btn-primary">
                        Explorar Alojamientos
                    </a>
                </div>
            <?php else: ?>
                <div class="alojamientos-grid">
                    <?php foreach ($alojamientos as $alojamiento): ?>
                        <div class="alojamiento-card">
                            <?php if ($alojamiento['imagen']): ?>
                                <div class="alojamiento-image">
                                    <img src="<?= htmlspecialchars($alojamiento['imagen']) ?>" 
                                         alt="<?= htmlspecialchars($alojamiento['nombre']) ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="alojamiento-content">
                                <h3><?= htmlspecialchars($alojamiento['nombre']) ?></h3>
                                
                                <p class="alojamiento-location">
                                    📍 <?= htmlspecialchars($alojamiento['ubicacion']) ?>
                                </p>
                                
                                <p class="alojamiento-description">
                                    <?= htmlspecialchars($alojamiento['descripcion']) ?>
                                </p>
                                
                                <div class="alojamiento-details">
                                    <span>👥 <?= $alojamiento['capacidad'] ?> personas</span>
                                    <span>🛏️ <?= $alojamiento['habitaciones'] ?> hab.</span>
                                    <span>🚿 <?= $alojamiento['banos'] ?> baños</span>
                                </div>
                                
                                <div class="alojamiento-amenities">
                                    <?php if ($alojamiento['wifi']): ?>
                                        <span class="amenity">📶 WiFi</span>
                                    <?php endif; ?>
                                    <?php if ($alojamiento['estacionamiento']): ?>
                                        <span class="amenity">🚗 Parking</span>
                                    <?php endif; ?>
                                    <?php if ($alojamiento['piscina']): ?>
                                        <span class="amenity">🏊 Piscina</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="alojamiento-footer">
                                    <div class="price">
                                        $<?= number_format($alojamiento['precio'], 2) ?>
                                        <span>/noche</span>
                                    </div>
                                    
                                    <form method="POST" action="/alojamiento/remove" 
                                          onsubmit="return confirm('¿Eliminar este alojamiento de tus selecciones?')">
                                        <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="selection-date">
                                    Seleccionado el <?= date('d/m/Y H:i', strtotime($alojamiento['fecha_seleccion'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>