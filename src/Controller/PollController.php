<?php
namespace App\Controller;

use App\Repository\PollRepository;
use App\Repository\PollItemRepository;
use App\Repository\UserPollItemRepository;
use App\Repository\CommentRepository;
use App\Entity\Poll;
use App\Entity\PollItem;
use App\Entity\UserPollItem;
use App\Entity\Comment;

class PollController extends Controller {
    public function list() {
        $repo = new PollRepository();
        $polls = $repo->findAll();
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        if ($categoryId) {
            $polls = array_filter($polls, function($poll) use ($categoryId) {
                return $poll->getCategoryId() == $categoryId;
            });
        }
        if ($search) {
            $polls = array_filter($polls, function($poll) use ($search) {
                return stripos($poll->getTitle(), $search) !== false || stripos($poll->getDescription(), $search) !== false;
            });
        }
        $this->render('poll/list', ['polls' => $polls]);
    }
    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /');
            exit;
        }
        $repo = new PollRepository();
        $poll = $repo->find($id);
        $itemRepo = new PollItemRepository();
        $items = $itemRepo->findByPollId($id);
        $voteRepo = new UserPollItemRepository();
        $results = $voteRepo->countVotes($id);
        $userVote = null;
        if (!empty($_SESSION['user'])) {
            $userVote = $voteRepo->getUserVote($_SESSION['user']->getId(), $id);
        }
        $commentRepo = new CommentRepository();
        $comments = $commentRepo->findByPollId($id);
        $this->render('poll/show', ['poll' => $poll, 'items' => $items, 'results' => $results, 'userVote' => $userVote, 'comments' => $comments]);
    }
    public function create() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $this->render('poll/create');
    }

    public function createPost() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
            $options = isset($_POST['options']) ? $_POST['options'] : '';

            if (!$title || !$description || !$categoryId || !$options) {
                header('Location: /poll/create');
                exit;
            }

            $user = $_SESSION['user'];
            $poll = new Poll(null, $title, $description, $user->getId(), $categoryId);
            
            $pollRepo = new PollRepository();
            $poll = $pollRepo->create($poll);

            $optionsList = array_filter(array_map('trim', explode("\n", $options)));
            $itemRepo = new PollItemRepository();

            foreach ($optionsList as $optionName) {
                if (!empty($optionName)) {
                    $item = new PollItem(null, $optionName, $poll->getId());
                    $itemRepo->create($item);
                }
            }

            header('Location: /poll/?id=' . $poll->getId());
            exit;
        }

        header('Location: /poll/create');
        exit;
    }

  
    public function vote() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pollId = isset($_GET['id']) ? intval($_GET['id']) : null;
            $itemId = isset($_POST['option']) ? intval($_POST['option']) : null;

            if (!$pollId || !$itemId) {
                header('Location: /poll/list');
                exit;
            }

            $user = $_SESSION['user'];
            $voteRepo = new UserPollItemRepository();
            $voteRepo->removeVotesForUserAndPoll($user->getId(), $pollId);

            $vote = new UserPollItem($user->getId(), $itemId);
            $voteRepo->addVote($vote);

            header('Location: /poll/?id=' . $pollId);
            exit;
        }

        header('Location: /poll/list');
        exit;
    }

    public function edit() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /poll/list');
            exit;
        }

        $repo = new PollRepository();
        $poll = $repo->find($id);

        if (!$poll || $poll->getUserId() != $_SESSION['user']->getId()) {
            header('Location: /poll/list');
            exit;
        }

        $itemRepo = new PollItemRepository();
        $items = $itemRepo->findByPollId($id);

        $this->render('poll/edit', ['poll' => $poll, 'items' => $items]);
    }

    public function editPost() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;

            if (!$id || !$title || !$description || !$categoryId) {
                header('Location: /poll/edit/?id=' . $id);
                exit;
            }

            $repo = new PollRepository();
            $poll = $repo->find($id);

            if (!$poll || $poll->getUserId() != $_SESSION['user']->getId()) {
                header('Location: /poll/list');
                exit;
            }

            $poll->setTitle($title);
            $poll->setDescription($description);
            $poll->setCategoryId($categoryId);

            $repo->update($poll);

            header('Location: /poll/?id=' . $id);
            exit;
        }

        header('Location: /poll/list');
        exit;
    }

    public function addComment() {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pollId = isset($_POST['poll_id']) ? intval($_POST['poll_id']) : null;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';

            if (!$pollId || !$content) {
                header('Location: /poll/?id=' . $pollId);
                exit;
            }

            $user = $_SESSION['user'];
            $comment = new Comment(null, $content, $user->getId(), $pollId);
            $commentRepo = new CommentRepository();
            $commentRepo->create($comment);

            header('Location: /poll/?id=' . $pollId);
            exit;
        }

        header('Location: /poll/list');
        exit;
    }
}
