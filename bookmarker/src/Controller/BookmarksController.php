<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 * @method \App\Model\Entity\Bookmark[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookmarksController extends AppController
{
    /**
     *
     * @param array|null $user
     * @return bool
     */
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['index', 'add', 'tags'])) {
            return true;
        }

        if (!$this->request->getParam('pass.0')) {
            return false;
        }

        $id = $this->request->getParam('pass.0');
        $bookmark = $this->Bookmarks->get($id);
        if ($bookmark->user_id == $user['id']) {
            return true;
        }

        return parent::isAuthorized($user);
    }


    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $bookmarks = $this->paginate($this->Bookmarks);

        $this->set(compact('bookmarks'));
    }

    public function view($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Users', 'Tags'],
        ]);

        $this->set(compact('bookmark'));
    }

    public function add()
    {
        $bookmark = $this->Bookmarks->newEmptyEntity();
        if ($this->request->is('post')) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            $bookmark->user_id = $this->Auth->user('id');

        if ($this->Bookmarks->save($bookmark)) {
            $this->Flash->success('The bookmark has been saved.');

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error('The bookmark could not be saved. Please, try again.');
    }
    $tags = $this->Bookmarks->Tags->find('list')->all();
    $this->set(compact('bookmark', 'tags'));
    }


    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
        'contain' => ['Tags']
    ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            $bookmark->user_id = $this->Auth->user('id');

        if ($this->Bookmarks->save($bookmark)) {
            $this->Flash->success('The bookmark has been saved.');

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error('The bookmark could not be saved. Please, try again.');
    }
    $tags = $this->Bookmarks->Tags->find('list')->all();
    $this->set(compact('bookmark', 'tags'));
}


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) {
            $this->Flash->success(__('The bookmark has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmark could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function tags()
    {
        $tags = $this->request->getParam('pass');

        $bookmarks = $this->Bookmarks->find('tagged', [
            'tags' => $tags
        ]);

        $this->set(compact('bookmarks', 'tags'));
    }
}
