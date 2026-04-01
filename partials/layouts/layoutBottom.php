        </div> <!-- END flex-1 -->

        <!-- 🔥 FOOTER START -->
        <footer class="d-footer mt-auto px-6 py-4 bg-white dark:bg-neutral-900 border-t border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between gap-3">
                <p class="mb-0 text-neutral-600 dark:text-white">
                    © <?= date('Y'); ?> <?= e(getSetting('site_title', $pdo)); ?>. All Rights Reserved.
                </p>
                <p class="mb-0 text-neutral-600 dark:text-white">
                    Designed for <span class="text-primary-600">Ecommerce</span>
                </p>
            </div>
        </footer>
        <!-- 🔥 FOOTER END -->

    </main>

    <?php include __DIR__ . '/../script.php'; ?>

</body>
</html>