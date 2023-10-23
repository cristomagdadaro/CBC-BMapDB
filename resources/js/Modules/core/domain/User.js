export default class User
{
    constructor(resp)
    {
        this.id = resp.id;
        this.fname = resp.fname;
        this.mname = resp.mname;
        this.lname = resp.lname;
        this.suffix = resp.suffix;
        this.account_for = resp.account_for;
        this.email = resp.email;
        this.mobile_no = resp.mobile_no;
        this.current_team_id = resp.current_team_id;
    }

    getFullName()
    {
        return this.fname + " " + this.mname + " " + this.lname + " " + this.suffix;
    }
}
