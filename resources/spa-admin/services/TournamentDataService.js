
import Connection from '../core/Connection';

const API_BASE = '/api/tournament/';

export default class TournamentDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
