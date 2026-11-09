// The scripts of every page of the admin (admin/_scripts.blade.php)
import './FccLattice.js';
import './LogoBalls.js';
import './AdminTabs.js';
import './AdminTables.js';
import { renderMath, ADMIN_DELIMITERS } from './math.js';
import { setupDateInputs } from './date-inputs.js';

renderMath(ADMIN_DELIMITERS);
setupDateInputs();
