        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('const.site_setting.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}" style="color: #FFC700; text-decoration: none;">Visit our website</a> |
                <a href="mailto:support@{{ parse_url(config('app.url'), PHP_URL_HOST) }}" style="color: #FFC700; text-decoration: none;">Contact Support</a>
            </p>
        </div>
        </div>
        </body>

        </html>