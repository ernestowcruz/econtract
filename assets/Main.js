import React from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
  } from "react-router-dom";
import ProjectList from "./pages/ProjectList"
import ProjectCreate from "./pages/ProjectCreate"
import ProjectEdit from "./pages/ProjectEdit"
import ProjectShow from "./pages/ProjectShow"
  
function Main() {
    return (
        <Router>
            <Switch>
                <Route exact path="/"  component={ProjectList} />
                <Route path="/create"  component={ProjectCreate} />
                <Route path="/edit/:id"  component={ProjectEdit} />
                <Route path="/show/:id"  component={ProjectShow} />
            </Switch>
        </Router>
    );
}
  
export default Main;
  
if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}