<footer>
    <p class="footer">
        Page rendered in <strong>{elapsed_time}</strong> seconds.
        Memory Usage : <strong>{memory_usage}</strong>.
        <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
    </p>
</footer>