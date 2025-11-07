<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="admin-container">
    <div class="container">
        <!-- Header del Panel Admin -->
        <div class="dashboard-header">
            <div>
                <h1>Panel de Administrador</h1>
                <p>Gestiona los alojamientos del sistema</p>
            </div>
            <a href="<?= UrlHelper::to('/') ?>" class="btn btn-secondary">
                ← Ver Página Principal
            </a>
        </div>

        <!-- Estadísticas Generales -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">🏠</div>
                <div class="stat-content">
                    <h3><?= $totalAlojamientos ?></h3>
                    <p>Total Alojamientos</p>
                </div>
            </div>
            
            <div class="stat-card stat-success">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3><?= $totalUsuarios ?></h3>
                    <p>Usuarios Registrados</p>
                </div>
            </div>
            
            <div class="stat-card stat-info">
                <div class="stat-icon">⭐</div>
                <div class="stat-content">
                    <h3><?= $totalSelecciones ?></h3>
                    <p>Total Selecciones</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('add')">
                ➕ Agregar Alojamiento
            </button>
            <button class="tab-btn" onclick="showTab('list')">
                📋 Ver Todos
            </button>
        </div>

        <!-- Tab: Agregar Alojamiento -->
        <div id="tab-add" class="tab-content active">
            <div class="form-card">
                <h2>Agregar Nuevo Alojamiento</h2>
                
                <form method="POST" action="<?= UrlHelper::to('admin/alojamiento/create') ?>" class="admin-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre del Alojamiento *</label>
                            <input type="text" id="nombre" name="nombre" required 
                                   placeholder="Ej: Villa de Lujo con Piscina">
                        </div>
                        
                        <div class="form-group">
                            <label for="ubicacion">Ubicación *</label>
                            <input type="text" id="ubicacion" name="ubicacion" required 
                                   placeholder="Ej: Marbella, España">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" required rows="4"
                                  placeholder="Describe el alojamiento..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="precio">Precio por Noche ($) *</label>
                            <input type="number" id="precio" name="precio" step="0.01" 
                                   required min="0" placeholder="85.00">
                        </div>
                        
                        <div class="form-group">
                            <label for="capacidad">Capacidad (personas) *</label>
                            <input type="number" id="capacidad" name="capacidad" 
                                   required min="1" placeholder="4">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="habitaciones">Habitaciones *</label>
                            <input type="number" id="habitaciones" name="habitaciones" 
                                   required min="1" placeholder="2">
                        </div>
                        
                        <div class="form-group">
                            <label for="banos">Baños *</label>
                            <input type="number" id="banos" name="banos" 
                                   required min="1" placeholder="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="imagen">URL de la Imagen</label>
                        <input type="url" id="imagen" name="imagen" 
                               placeholder="https://ejemplo.com/imagen.jpg">
                        <small>Opcional: URL de una imagen del alojamiento</small>
                    </div>

                    <div class="form-group">
                        <label>Comodidades</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="wifi" value="1">
                                <span>📶 WiFi</span>
                            </label>
                            
                            <label class="checkbox-label">
                                <input type="checkbox" name="estacionamiento" value="1">
                                <span>🚗 Estacionamiento</span>
                            </label>
                            
                            <label class="checkbox-label">
                                <input type="checkbox" name="piscina" value="1">
                                <span>🏊 Piscina</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        ➕ Agregar Alojamiento
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab: Lista de Alojamientos -->
        <div id="tab-list" class="tab-content">
            <h2>Todos los Alojamientos (<?= count($alojamientos) ?>)</h2>
            
            <?php if (empty($alojamientos)): ?>
                <div class="empty-state">
                    <p>No hay alojamientos registrados</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Precio</th>
                                <th>Capacidad</th>
                                <th>Hab.</th>
                                <th>Comodidades</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alojamientos as $alojamiento): ?>
                                <tr>
                                    <td><?= $alojamiento['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($alojamiento['nombre']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($alojamiento['ubicacion']) ?></td>
                                    <td>$<?= number_format($alojamiento['precio'], 2) ?></td>
                                    <td><?= $alojamiento['capacidad'] ?> pers.</td>
                                    <td><?= $alojamiento['habitaciones'] ?></td>
                                    <td class="amenities-cell">
                                        <?php if ($alojamiento['wifi']): ?>
                                            <span title="WiFi">📶</span>
                                        <?php endif; ?>
                                        <?php if ($alojamiento['estacionamiento']): ?>
                                            <span title="Parking">🚗</span>
                                        <?php endif; ?>
                                        <?php if ($alojamiento['piscina']): ?>
                                            <span title="Piscina">🏊</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($alojamiento['disponible']): ?>
                                            <span class="badge badge-success">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">No disponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($alojamiento['fecha_creacion'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Ocultar todos los tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Desactivar todos los botones
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Mostrar el tab seleccionado
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>