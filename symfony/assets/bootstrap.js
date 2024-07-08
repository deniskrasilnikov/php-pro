import { startStimulusApp } from '@symfony/stimulus-bundle';
import AuthorsController from './controllers/authors_controller.js';

const app = startStimulusApp();
app.register('authors', AuthorsController);
