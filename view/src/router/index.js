import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Research from '@/components/Research'
import Publications from '@/components/Publications'
import Members from '@/components/Members'
import Management from '@/components/Management'
import Events from '@/components/Events'
import NewTask from '@/components/NewTask'
import TaskManagement from '@/components/TaskManagement'
import Task from '@/components/Task'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      component: Home
    },

    {
      path: '/home',
      name: 'Home',
      component: Home
    },

    {
      path: '/research',
      name: 'Research',
      component: Research
    },

    {
      path: '/publications',
      name: 'Publications',
      component: Publications
    },

    {
      path: '/members',
      name: 'Members',
      component: Members
    },

    {
      path: '/management',
      name: 'Management',
      component: Management
    },

    {
      path: '/newtask',
      name: 'NewTask',
      component: NewTask
    },

    {
      path: '/events',
      name: 'Events',
      component: Events
    },

    {
      path: '/taskmanagement',
      name: 'TaskManagement',
      component: TaskManagement
    },

    {
      path: '/task/:id',
      name: 'Task',
      component: Task
    }
  ]
})
