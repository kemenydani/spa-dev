import Connection from './Connection';

const API_BASE = '/api/user/';

export default class UserDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
};
