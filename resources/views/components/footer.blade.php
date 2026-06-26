<footer class="app-footer">
    <div class="footer-content">
        <div class="footer-copyright">
            <p>&copy; 2025 GreenRoute. All rights reserved.</p>
        </div>
        <div class="footer-links">
            <a href="{{ route('terms-of-service') }}" class="footer-link">Terms of Service</a>
            <span class="footer-separator">|</span>
            <a href="{{ route('privacy-policy') }}" class="footer-link">Privacy Policy</a>
        </div>
    </div>
    
    <style>
        .app-footer {
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
            margin-top: auto;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .footer-copyright {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .footer-copyright p {
            margin: 0;
        }
        
        .footer-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .footer-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        
        .footer-link:hover {
            color: #047857;
            text-decoration: underline;
        }
        
        .footer-separator {
            color: #cbd5e1;
        }
        
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .app-footer {
                padding: 1rem;
            }
        }
    </style>
</footer>
