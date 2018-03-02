
import Connection from '../core/Connection';

const API_BASE = '/api/stream/';

export default class StreamDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
