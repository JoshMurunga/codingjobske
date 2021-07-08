import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Pagination from './pagination';
import Favorite from './favorite';
import _ from 'lodash';
import "bootstrap/dist/css/bootstrap.css";
import "../index.css";

class Reply extends Component {
    constructor(props) {
        super(props);

        this.state = {
            pageSize: 10,
        }
    }

    componentDidUpdate() {
        console.log(this.props.pages)
    }

    renderUpdate(user, reply) {
        if (this.props.authorize == user || this.props.authorize == 550) {
            return (
                <div className="card-footer level">
                    <button className="btn btn-success btn-sm mr-1" onClick={() => this.props.onEdit(reply)}>Edit</button>
                    <button className="btn btn-danger btn-sm" onClick={() => this.props.onReplyDelete(reply)}>Delete</button>
                </div>
            );
        }
    }

    render() {
        const { pageSize  } = this.state;

        const { replies, login, onReplyChange, onEdit, onReplySubmit, onLike, pages, onPageChange, currentPage } = this.props;

        const startIndex = (currentPage - 1) * pageSize;

        return (
            <div>
                {replies.map(reply => (
                    <div key={reply.id}>
                        <div className="card" >
                            <div className="card-header">
                                <div className="level">
                                    <h6 className="flex">
                                        <a href={`/profiles/${reply.owner.name}`}>{reply.owner.name}</a> says {reply.humanCreatedAt}
                                    </h6>
                                    <Favorite count={reply.favoritesCount} isFavorited={reply.isFavorited} isAuthorized={login} onClick={() => onLike(reply)} />
                                </div>
                            </div>
                            <div className="card-body">
                                <form onSubmit={(e) => onReplySubmit(e, reply)} style={{ display: reply.edit ? "block" : "none" }}>
                                    <textarea autoFocus className="form-control" name="body" id="body" value={reply.body} onChange={(e) => onReplyChange(e, reply)}></textarea>
                                    <br />
                                    <div className="text-right">
                                        <a onClick={() => onEdit(reply)} className="btn btn-link btn-sm mr-1">Cancel</a>
                                        <button onClick={() => onEdit(reply)} className="btn btn-primary btn-sm">Update</button>
                                    </div>
                                </form>
                                <div style={{ display: reply.display ? "none" : "block" }}>{reply.body}</div>
                            </div>
                            <div>{this.renderUpdate(reply.owner.id, reply)}</div>
                        </div>
                        <br />
                    </div>
                ))}
                <Pagination 
                    pages={pages}
                    // itemsCount={replies.length} 
                    // pageSize={pageSize} 
                    currentPage={currentPage} 
                    onPageChange={onPageChange} 
                />
                    
            </div>
        );
    }
}

export default Reply;

// if (document.getElementById('reply')) {
//     const element = document.getElementById('reply')
//     const props = Object.assign({}, element.dataset)
//     ReactDOM.render(<Reply {...props} />, element);
// }