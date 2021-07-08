import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import "bootstrap/dist/css/bootstrap.css";
import "../index.css";

class Flash extends Component {
    constructor(props){
        super(props);
		
		this.state = {
			showDiv: true
		}
    }
  
	componentDidMount() {
		this.timer = setTimeout(() => this.setState({ showDiv: false }), 3000)
	}
	
	componentWillUnmount() {
		clearTimeout(this.timer);
	}
	
    render() {
        if(this.props.message=='') return null;

        return (
            <div className="alert alert-success alert-flash" role="alert" style={{ visibility: this.state.showDiv ? "visible" : "hidden" }}>
                Success! {this.props.message}
            </div>
        );
    }
}

export default Flash;

if (document.getElementById('flash')) {
    // find element by id
    const element = document.getElementById('flash')
      
    // create new props object with element's data-attributes
    // result: {tsId: "1241"}
    const props = Object.assign({}, element.dataset)

    // render element with props (using spread)
    ReactDOM.render(<Flash {...props}/>, element);

    // ReactDOM.render(<Flash />, document.getElementById('flash'));
}
