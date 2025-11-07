<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Encuentra tu Alojamiento Perfecto</h1>
        <p>Descubre los mejores alojamientos para tus vacaciones</p>
        
        <!-- Buscador -->
        <div class="search-box">
            <form method="GET" action="/buscar" class="search-form">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Buscar alojamientos..."
                    value="<?= htmlspecialchars($search ?? '') ?>"
                >
                <input 
                    type="text" 
                    name="ubicacion" 
                    placeholder="Ubicación"
                    value="<?= htmlspecialchars($ubicacion ?? '') ?>"
                >
                <button type="submit" class="btn btn-primary">
                    🔍 Buscar
                </button>
            </form>
        </div>

        <div class="features">
            <div class="feature">✓ Reservas seguras</div>
            <div class="feature">✓ Mejor precio garantizado</div>
            <div class="feature">✓ Atención 24/7</div>
        </div>
    </div>
</section>

<!-- Alojamientos -->
<section class="alojamientos-section">
    <div class="container">
        <h2>Alojamientos Disponibles</h2>
        <p class="section-subtitle">
            <?php if (Session::isAuthenticated()): ?>
                Selecciona los alojamientos que te interesen para agregarlos a tu cuenta
            <?php else: ?>
                Inicia sesión para poder seleccionar alojamientos
            <?php endif; ?>
        </p>

        <?php if (empty($alojamientos)): ?>
            <div class="empty-state">
                <p>No se encontraron alojamientos</p>
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
                                <?= htmlspecialchars(substr($alojamiento['descripcion'], 0, 100)) ?>...
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
                                
                                <?php if (Session::isAuthenticated()): ?>
                                    <?php if (in_array($alojamiento['id'], $selectedIds)): ?>
                                        <span class="badge badge-success">✓ Seleccionado</span>
                                    <?php else: ?>
                                        <form method="POST" action="/alojamiento/select" style="display: inline;">
                                            <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Seleccionar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>