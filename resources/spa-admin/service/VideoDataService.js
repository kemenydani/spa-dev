
import Connection from '../core/Connection';

const API_BASE = '/api/video/';

export default class VideoDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
