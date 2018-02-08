
export default class DataService {
	
	getAll()
	{
		return this.Connection.get('all').then( response => response.data );
	}
	
}
