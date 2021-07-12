import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Reply from './Reply';

class Thread extends Component {
    constructor(props) {
        super(props);

        this.state = {
            posts: [],
            replies: [],
            reply: [],
            creator: {},
            currentPage: 1,
            body: ''
        }
    }

    async componentDidMount() {
        await this.fetchData();
    }

    async fetchData(page) {
        if(!page) {
            let query = location.search.match(/page=(\d+)/);

            page = query ? query[1] : 1;
        }

        const { data } = await axios.get(location.pathname + '?page=' + page);
        this.setState({
            posts: data.thread,
            replies: data.replies,
            reply: data.replies.data,
            creator: data.thread.creator,
            currentPage: data.replies.current_page
        });

        window.scrollTo(0, 0);
    }

    handlePageChange = (page,type) => {
        if (type == "next") {
            page = page + 1;
        } else if (type == "previous") {
            page = page - 1;
        }
        this.setState({ currentPage: page });
        this.fetchData(page);
        history.pushState(null, null, '?page=' + page);
    }

    handleDeleteThread = async () => {
        await axios.delete(location.pathname);
    }

    handleDelete = async reply => {
        const originalReplies = this.state.reply;

        const newReplies = originalReplies.filter(r => r.id !== reply.id);
        this.setState({ reply: newReplies })

        try {
            await axios.delete('/replies/' + reply.id);
        } catch (ex) {
            if (ex.response && ex.response.status === 404) console.log("This movie has already been deleted.");
            replies = originalReplies;
            this.setState({ reply: originalReplies })
        }
    }

    handleChange = e => {
        const body = e.currentTarget.value;
        this.setState({ body });
    }

    handleSubmit = e => {
        e.preventDefault();

        this.doSubmit();
    }

    handleEdit = reply => {
        const replies = [...this.state.reply];
        const index = replies.indexOf(reply);
        replies[index] = { ...replies[index] };
        replies[index].edit = !replies[index].edit;
        replies[index].display = !replies[index].display;
        this.setState({ reply: replies });
    }

    handleReplyChange = (e, reply) => {
        const replies = [...this.state.reply];
        const index = replies.indexOf(reply);
        replies[index] = { ...replies[index] };
        replies[index].body = e.currentTarget.value;
        this.setState({ reply: replies });
    }

    handleLike = async reply => {
        const replies = [...this.state.reply];
        const index = replies.indexOf(reply);
        replies[index] = { ...replies[index] };

        if(replies[index].isFavorited) {
            replies[index].isFavorited = !replies[index].isFavorited;
            replies[index].favoritesCount--;
            this.setState({ reply: replies });
            await axios.delete('/replies/' + reply.id + '/favorites');
        } else {
            replies[index].isFavorited = !replies[index].isFavorited;
            replies[index].favoritesCount++;
            this.setState({ reply: replies });
            await axios.post('/replies/' + reply.id + '/favorites');
        }
    }

    handleReplySubmit = (e, reply) => {
        e.preventDefault();

        this.doReplySubmit(reply);
    }

    doReplySubmit = async reply => {
        await axios.patch('/replies/' + reply.id, { body: reply.body });
    }

    doSubmit = async () => {
        await axios.post(location.pathname + '/replies', { body: this.state.body }).then(({ data }) => {
            this.setState({
                posts: data.thread,
                replies: data.replies,
                reply: data.replies.data,
                body: ''
            });
        });
    }

    renderDelete(user) {
        if(this.props.authorize == user || this.props.authorize == 550) {
            return (
                <button className="btn btn-link" onClick={this.handleDeleteThread}>Delete Thread</button>
            );
        }
    }

    renderPost() {
        if(this.props.login!=='1') return <div className="text-center">please <a href="/login">login</a> to add comment</div>;

        return (
            <form onSubmit={(e) => this.handleSubmit(e)}>
                <textarea 
                    autoFocus 
                    className="form-control" 
                    name="body" id="body" 
                    rows="5" 
                    placeholder="Something to say?"
                    value={this.state.body} 
                    onChange={(e) => this.handleChange(e)}
                    required ></textarea>
                <br />
                <div className="text-right">
                    <button className="btn btn-primary">Post</button>
                </div>
            </form>
        );
    }

    render() {
        const { posts:post, creator, replies, reply, currentPage } = this.state;

        const { authorize, login} = this.props;

        return (
            <div>
                <div className="row" >
					<div className="col-md-8">
						<div className="card">
							<div className="card-header">
								<div className="level">
									<span className="flex">
										<a href={`/profiles/${creator.name}`}>{creator.name}</a> posted: {post.title}
									</span>
									{this.renderDelete(creator.id)}
								</div>
							</div>
							<div className="card-body">
								{post.body}
							</div>
						</div>
						<br />
						{this.renderPost()}
                        <br />
                        <Reply 
                            replies={reply}
                            pages={replies} 
                            login={login} 
                            authorize={authorize}
                            onEdit={this.handleEdit}
                            onReplyChange={this.handleReplyChange}
                            onReplySubmit={this.handleReplySubmit}
                            onReplyDelete={this.handleDelete}
                            onLike={this.handleLike}
                            onPageChange={this.handlePageChange}
                            currentPage={currentPage}
                        />
					</div>
                    <div className="col-md-4">
                        <div className="card">
                            <div className="card-body">
                                <p>
                                    This thread was published  by <a href={`/profiles/${creator.name}`}>
                                        {creator.name}
                                    </a>, and currently has {post.replies_count} { post.replies_count == 1 ? "comment" : "comments" }
                                </p>
                            </div>
                        </div>
                    </div>
				</div> 
            </div>
        );
    }
}

export default Thread;

if (document.getElementById('thread')) {
    const element = document.getElementById('thread')
    const props = Object.assign({}, element.dataset)
    ReactDOM.render(<Thread {...props} />, element);
}
