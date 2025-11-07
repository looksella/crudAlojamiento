</main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Sobre Alojamientos</h4>
                    <p>Tu plataforma confiable para encontrar el alojamiento perfecto.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="/">Inicio</a></li>
                        <li><a href="/buscar">Buscar</a></li>
                        <?php if (Session::isAuthenticated()): ?>
                            <li><a href="/dashboard">Mi Cuenta</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p>¿Necesitas ayuda? Estamos disponibles 24/7</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Alojamientos. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="/public/js/app.js"></script>
</body>
</html>