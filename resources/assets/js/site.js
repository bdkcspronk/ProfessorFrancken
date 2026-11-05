// The scripts of every page of the website (layout/layout.blade.php and homepage/layout.blade.php)
import './FccLattice.js';
import './LogoBalls.js';
import './ExternalLinks.js';
import './SiteMenu.js';
import './AccountMenu.js';
import './SiteSearch.js';
import { renderMath, SITE_DELIMITERS } from './math.js';
import { setupDateInputs } from './date-inputs.js';

renderMath(SITE_DELIMITERS);
setupDateInputs();
